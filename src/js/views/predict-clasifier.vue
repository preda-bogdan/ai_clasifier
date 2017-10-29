<template>
	<div class="col-12 text-comfortaa mb-2">
		<div class="input-group">
			<span class="input-group-addon"><b class="text-primary">Lala</b>Clasifier</span>
			<input type="text" class="form-input" placeholder="I say ..." readonly :value="prediction">
			<button class="btn btn-info input-group-btn" @click="predict()"><i class="fa fa-eercast" aria-hidden="true"></i> Predict</button>
		</div>
	</div>
</template>

<script>

module.exports = {
	name: 'predict-clasifier',
	created () {
	},
	computed: {
		prediction: function () {
			if ( this.$store.state.prediction && this.$store.state.prediction.length > 0 ) {
				return this.$store.state.prediction[0]
			}
		}
	},
	methods: {
		predict () {
			this.$store.dispatch( 'getPrediction', { feedback: this.$store.state.randomFeedback.feedback } )
		}
	},
	components: {
	}
}
</script>