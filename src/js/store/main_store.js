import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'

Vue.use( Vuex )
Vue.use( VueResource )

export default new Vuex.Store( {
	state: {
		randomFeedback: [],
		trainingTable: [],
		accuracy: [],
		prediction: []
	},
	getters: {
		getRandomFeedback ( state ) {
			return state.randomFeedback
		},
		getTrainingTable ( state ) {
			return state.trainingTable
		}
	},
	mutations: {
		updateRandomFeedback ( state, data ) {
			state.randomFeedback = data
		},
		updateTrainingTable ( state, data ) {
			state.trainingTable = data
		},
		updateAccuracy ( state, data ) {
			state.accuracy = data
		},
		updatePrediction ( state, data ) {
			state.prediction = data
		}
	},
	actions: {
		fetchRandomFeedback ( { commit } ) {
			Vue.http( {
				url: '?api',
				method: 'POST',
				params: { 'req': 'random_feedback' },
				responseType: 'json'
			} ).then( function ( response ) {
				commit( 'updateRandomFeedback', response.data )
				console.log( 'Fetching random feedback.' )
				console.log( response.data )
			}, function () {
				console.error( 'Error retrieving random feedback.' )
			} )
		},
		updateLabel ( { commit }, data ) {
			data.id = this.state.randomFeedback._id
			Vue.http( {
				url: '?api',
				method: 'POST',
				params: { 'req': 'update_feedback_label' },
				body: data,
				responseType: 'json'
			} ).then( function ( response ) {
				console.log( 'Updated feedback label.' )
				console.log( response.data )
			}, function () {
				console.error( 'Error updating feedback label.' )
			} )
		},
		fetchTrainingData ( { commit } ) {
			Vue.http( {
				url: '?api',
				method: 'POST',
				params: { 'req': 'training_table' },
				responseType: 'json'
			} ).then( function ( response ) {
				commit( 'updateTrainingTable', response.data )
				console.log( 'Fetching training data.' )
				console.log( response.data )
			}, function () {
				console.error( 'Error retrieving training data.' )
			} )
		},
		newTraining ( { commit } ) {
			Vue.http( {
				url: '?api',
				method: 'POST',
				params: { 'req': 'new_training' },
				responseType: 'json'
			} ).then( function ( response ) {
				commit( 'updateAccuracy', response.data )
				console.log( 'Fetching requesting new training.' )
				console.log( response.data )
			}, function () {
				console.error( 'Error retrieving new training' )
			} )
		},
		getPrediction ( { commit }, data ) {
			Vue.http( {
				url: '?api',
				method: 'POST',
				params: { 'req': 'get_prediction' },
				body: data,
				responseType: 'json'
			} ).then( function ( response ) {
				commit( 'updatePrediction', response.data )
				console.log( 'Fetching prediction.' )
				console.log( response.data )
			}, function () {
				console.error( 'Error retrieving prediction.' )
			} )
		}
	}
} )
