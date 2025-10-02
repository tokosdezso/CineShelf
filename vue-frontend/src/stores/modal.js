import { defineStore } from 'pinia'

export const useAddMovieModalStore = defineStore('addMovieModal', {
  state: () => ({
    isOpen: false,
    movieId: null,
    movieTitle: null,
  }),
  actions: {
    open(movieId, movieTitle = '') {
      this.isOpen = true
      this.movieId = movieId
      this.movieTitle = movieTitle
    },
    close() {
      this.isOpen = false
      this.movieId = null
      this.movieTitle = null
    }
  },
})