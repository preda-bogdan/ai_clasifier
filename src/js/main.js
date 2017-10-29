import Vue from 'vue'

import store from './store/main_store.js'
import LiveClasifier from './views/live-clasifier.vue'
import PredictClasifier from './views/predict-clasifier.vue'
import TrainClasifier from './views/train-clasifier.vue'

window.onload = function () {
	const LalaApp = new Vue( {
		el: '#lala_app',
		store,
		created () {
			store.dispatch( 'fetchRandomFeedback' )
			store.dispatch( 'fetchTrainingData' )
		},
		components: {
			LiveClasifier,
			PredictClasifier,
			TrainClasifier
		}
	} )
}