export default {

	state: {
		user: null,
		userStatus: null,
	},

	getters: {
		user(state) {
			return state.user
		},
		userStatus(state) {
			return state.userStatus
		}
	},

	actions: {
		async fetchUser({ commit }, userId) {
            commit('SET_USER_STATUS', 'loading')

            try {
                const res = await axios.get(`users/${userId}`)
                commit('SET_USER', res.data)
                commit('SET_USER_STATUS', 'success')
            } catch (error) {
                commit('SET_USER_STATUS', 'error')
                console.log(`Cannot fetch user with id of ${userId}!`);
            }
		}
	},

	mutations: {
		SET_USER(state, user) {
			state.user = user
		},
		SET_USER_STATUS(state, status) {
			state.userStatus = status
		}
	},
}