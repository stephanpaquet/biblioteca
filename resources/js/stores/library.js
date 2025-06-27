import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

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

    function updateBookStatus(bookId, status) {
        const book = books.value.find(book => book.id === bookId)
        if (book && book.pivot) {
            book.pivot.status = status
        }
    }

    function isBookInLibrary(googleBookId) {
        return books.value.some(book => book.google_book_id === googleBookId)
    }

    async function addToLibrary(bookData) {
        isLoading.value = true
        error.value = null

        try {
            const response = await fetch('/api/library', {
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
                return { success: true, message: result.message }
            } else {
                error.value = result.message
                return { success: false, message: result.message }
            }
        } catch (err) {
            error.value = 'Failed to add book to library'
            return { success: false, message: 'Failed to add book to library' }
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
        addToLibrary
    }
})
