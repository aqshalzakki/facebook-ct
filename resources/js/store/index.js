import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import pageTitle from './modules/pageTitle'

Vue.use(Vuex)

export default new Vuex.Store({
	modules : {
		user,
		pageTitle
	}
}) 