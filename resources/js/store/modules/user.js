export default {

	state: {
		authUser: null,
		authUserStatus: null,
	},

	getters: {
		authUser(state) {
			return state.authUser
		},
		authUserStatus(state) {
			return state.authUserStatus
		}
	},

	actions: {
		async fetchAuthUser({ commit }) {
			try {
                const response = await axios.get('auth-user')
                commit('SET_AUTH_USER', response.data)
            } catch (e) {
                console.log('Unable to fetch a user!')
                commit('SET_AUTH_USER', null)
            } finally {
            	commit('SET_AUTH_USER_STATUS', true)
            }
		}
	},

	mutations: {
		SET_AUTH_USER(state, user) {
			state.authUser = user
		},
		SET_AUTH_USER_STATUS(state, status) {
			state.authUserStatus = status
		}
	},
}