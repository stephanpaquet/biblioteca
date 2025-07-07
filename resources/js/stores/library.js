import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useToast } from '../composables/useToast';

export const useLibraryStore = defineStore('library', () => {
    const books = ref([])
    const isLoading = ref(false)
    const error = ref(null)

    const bookCount = computed(() => books.value.length)
    const readBooks = computed(() =>
        books.value.filter(book => book.pivot?.status === 'read')
    )
    const currentlyReading = computed(() =>
        books.value.filter(book => book.pivot?.status === 'reading')
    )
    const wantToRead = computed(() =>
        books.value.filter(book => book.pivot?.status === 'want_to_read')
    )

    function setBooks(bookList) {
        books.value = bookList
    }

    function addBook(book) {
        const existingBook = books.value.find(b => b.google_book_id === book.google_book_id)
        if (!existingBook) {
            books.value.push(book)
        }
    }

    function removeBook(bookId) {
        const index = books.value.findIndex(book => book.id === bookId)
        if (index > -1) {
            books.value.splice(index, 1)
        }
    }

    async function updateBookStatus(googleBookId, status) {
        isLoading.value = true
        error.value = null

        try {
            // Find the book by google_book_id to get the actual book id
            const book = books.value.find(book => book.google_book_id === googleBookId)
            if (!book) {
                throw new Error('Book not found in library')
            }

            const response = await axios.patch(`/library/${book.id}/status`, {
                status: status
            })

            // Update local state only after successful API call
            if (book.pivot) {
                book.pivot.status = status
            }

            return { success: true, message: response.data.message }
        } catch (err) {
            console.error('Failed to update book status:', err)
            error.value = err.response?.data?.message || 'Failed to update book status'
            return { success: false, error: error.value }
        } finally {
            isLoading.value = false
        }
    }

    function isBookInLibrary(googleBookId) {
        return books.value.some(book => book.google_book_id === googleBookId)
    }

    async function addToLibrary(bookData) {
        const toast = useToast();
        isLoading.value = true
        error.value = null

        try {
            const response = await fetch(route('library.store'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(bookData)
            })

            const result = await response.json()

            if (response.ok) {
                addBook({ ...bookData, pivot: { status: bookData.status || 'want_to_read' } })
                toast.bookAdded(bookData.title);
                return { success: true, message: result.message }
            } else {
                error.value = result.message
                toast.error(result.message);
                return { success: false, message: result.message }
            }
        } catch (err) {
            const errorMsg = 'Failed to add book to library'
            error.value = errorMsg
            toast.error(errorMsg);
            return { success: false, message: errorMsg }
        } finally {
            isLoading.value = false
        }
    }

    async function removeFromLibrary(bookId) {
        const toast = useToast();
        isLoading.value = true
        error.value = null

        try {
            const response = await fetch(route('library.destroy', bookId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

            const result = await response.json()

            if (response.ok) {
                removeBook(bookId)
                toast.success('Book removed from library');
                return { success: true, message: result.message }
            } else {
                error.value = result.message
                toast.error(result.message);
                return { success: false, message: result.message }
            }
        } catch (err) {
            const errorMsg = 'Failed to remove book from library'
            error.value = errorMsg
            toast.error(errorMsg);
            return { success: false, message: errorMsg }
        } finally {
            isLoading.value = false
        }
    }

    async function syncBookWithGoogleApi(googleBookId) {
        const toast = useToast();
        isLoading.value = true
        error.value = null

        try {
            // Find the book in our library by Google Book ID
            const book = books.value.find(b => b.google_book_id === googleBookId)
            if (!book) {
                throw new Error('Book not found in library')
            }

            const response = await fetch(route('library.sync', book.id), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

            const result = await response.json()

            if (response.ok) {
                // Update the book in the local store with new data
                const bookIndex = books.value.findIndex(book => book.google_book_id === googleBookId)
                if (bookIndex > -1 && result.book) {
                    books.value[bookIndex] = { ...books.value[bookIndex], ...result.book }
                }
                toast.success('Book information synced successfully: ' + book.title);
                return { success: true, message: result.message }
            } else {
                error.value = result.message
                toast.error(result.message);
                return { success: false, message: result.message }
            }
        } catch (err) {
            const errorMsg = 'Failed to sync book information'
            error.value = errorMsg
            toast.error(errorMsg);
            return { success: false, message: errorMsg }
        } finally {
            isLoading.value = false
        }
    }

    return {
        books,
        isLoading,
        error,
        bookCount,
        readBooks,
        currentlyReading,
        wantToRead,
        setBooks,
        addBook,
        removeBook,
        updateBookStatus,
        isBookInLibrary,
        addToLibrary,
        removeFromLibrary,
        syncBookWithGoogleApi
    }
})
