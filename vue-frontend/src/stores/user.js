import { defineStore } from 'pinia'
import axiosClient from '../axios'
import router from '../router.js';

const useUserStore  = defineStore('user', {
  state: () => ({
    user: null,
  }),
  actions: {
    fetchUser() {
      return axiosClient.get('/api/user')
        .then(({data}) => {
          this.user = data
        })
        .catch((error) => {
          this.user = null
          console.error('Failed to fetch user:', error)
          router.push({name: 'Login'})
        })
    }
  },
})

export default useUserStore