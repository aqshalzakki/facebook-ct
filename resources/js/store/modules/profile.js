export default {

	state: {
		user: null,
		userStatus: null
	},

	getters: {
		user(state) {
			return state.user
		},
		friendship(state) {
			return state.user.data.attributes.friendship
		},
		userStatus(state) {
			return state.userStatus
		},
		friendButtonText(state, getters, rootState) {
			if (getters.friendship === null) {
				return 'Add Friend'
			}
			else if (getters.friendship.data.attributes.confirmed_at === null) {
				return 'Pending Friend Request'
			}
		}
	},

	actions: {
		async fetchUser({ commit }, userId) {
            commit('SET_USER_STATUS', 'loading')
 
            try {
                const res = await axios.get(`users/${userId}`)
                commit('SET_USER', res.data)
				commit('SET_USER_STATUS', 'success')
			} 
			catch (error) {
                commit(' SET_USER_STATUS', 'error')
                console.log(`Cannot fetch user with id of ${userId}!`);
            }
		},

		async sendFriendRequest({ commit }, friend_id) {
			commit('SET_BUTTON_TEXT', 'Loading')

			try {
				const { data } = await axios.post('friend-request', {
					friend_id
				})

				commit('SET_USER_FRIENDSHIP', data)
				commit('SET_BUTTON_TEXT', 'Pending Friend Request')
				console.log('Friend requested!');
				
			}
			catch(e) {
				console.log('Unknown error has been occured! please try again!');
				commit('SET_BUTTON_TEXT', 'Add Friend')
			}
		}
	},

	mutations: {
		SET_USER(state, user) {
			state.user = user
		},
		SET_USER_FRIENDSHIP(state, friendship) {
			state.user.data.attributes.friendship = friendship
		},
		SET_USER_STATUS(state, status) {
			state.userStatus = status
		},
		SET_BUTTON_TEXT(state, text) {
			state.friendButtonText = text
		}
	},
}