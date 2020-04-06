export default {

	state: {
		title: null
	},

	getters: {

	},

	actions: {
		setPageTitle({ commit }, title) {
			commit('SET_TITLE', title)
		}
	},

	mutations: {
		SET_TITLE(state, title) {
			state.title = title + ' | Facebook CT'

			document.title = state.title
		}
	},
}