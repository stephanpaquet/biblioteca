import { useToast as useVueToast } from 'vue-toastification';

export function useToast() {
  const toast = useVueToast();

  return {
    success: (message, options = {}) => {
      toast.success(message, {
        timeout: 3000,
        ...options,
      });
    },

    error: (message, options = {}) => {
      toast.error(message, {
        timeout: 5000,
        ...options,
      });
    },

    warning: (message, options = {}) => {
      toast.warning(message, {
        timeout: 4000,
        ...options,
      });
    },

    info: (message, options = {}) => {
      toast.info(message, {
        timeout: 3000,
        ...options,
      });
    },

    // Library specific methods
    bookAdded: (bookTitle) => {
      toast.success(`"${bookTitle}" added to your library!`, {
        timeout: 3000,
        icon: '📚',
      });
    },

    bookRemoved: (bookTitle) => {
      toast.info(`"${bookTitle}" removed from library`, {
        timeout: 2000,
        icon: '🗑️',
      });
    },

    loginSuccess: (userName) => {
      toast.success(`Welcome back, ${userName}!`, {
        timeout: 3000,
        icon: '👋',
      });
    },

    loginError: () => {
      toast.error('Invalid credentials. Please try again.', {
        timeout: 4000,
        icon: '❌',
      });
    },
  };
}
