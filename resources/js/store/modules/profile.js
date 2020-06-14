export default {

	state: {
		user: null,
		userStatus: null,
		posts: null,
		postsStatus: null,
	},

	getters: {
		user(state) {
			return state.user
		},
		posts(state) {
			return state.posts
		},
		statuses(state) {
			return {
				user: state.userStatus,
				posts: state.postsStatus,
			}
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
			catch {
                commit(' SET_USER_STATUS', 'error')
                console.log(`Cannot fetch user with id of ${userId}!`);
            }
		},
		async fetchUserPosts({ commit }, userId) {
			commit('SET_USER_POSTS_STATUS', "loading")

			try {
				const response = await axios.get(`users/${userId}/posts`)
				commit('SET_POSTS', response.data)
				commit('SET_USER_POSTS_STATUS', "success")
			} catch {
				console.log('Unable to Fetch The Posts!');
				commit('SET_USER_POSTS_STATUS', "error")
			}
		},

		async sendFriendRequest({ commit }, friend_id) {
			try {
				const { data } = await axios.post('friend-request', {
					friend_id
				})
				commit('SET_USER_FRIENDSHIP', data)
				console.log('Friend requested!');
			}
			catch{
				console.log('Unknown error has been occured! please try again!');
			}
		},
		async acceptFriendRequest({ commit }, user_id) {
			try {
				const { data } = await axios.post('friend-request-response', {
					user_id
				})
				commit('SET_USER_FRIENDSHIP', data)
				console.log('Friend requested!');
			}
			catch{
				console.log('Unknown error has been occured! please try again!');
			}
		},
		async ignoreFriendRequest({ commit }, user_id) {
			try {
				const { data } = await axios.delete('friend-request-response/delete', { data: { user_id } })

				commit('SET_USER_FRIENDSHIP', data)
				console.log('Friend requested!');
			}
			catch{
				console.log('Unknown error has been occured! please try again!');
			}
		},
	},

	mutations: {
		SET_USER(state, user) {
			state.user = user
		},
		SET_POSTS(state, posts) {
			state.posts = posts
		},
		SET_USER_FRIENDSHIP(state, friendship) {
			state.user.data.attributes.friendship = friendship
		},
		SET_USER_STATUS(state, status) {
			state.userStatus = status
		},
		SET_USER_POSTS_STATUS(state, status) {
			state.postsStatus = status
		},

	},
}