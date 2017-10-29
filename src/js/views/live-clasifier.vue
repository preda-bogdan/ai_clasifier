<template>
	<div class="col-12 text-comfortaa">
		<div class="input-group">
			<span class="input-group-addon"><b class="text-primary">Category:</b></span>
			<input type="text" class="form-input" readonly :value="category">
			<button class="btn btn-warning input-group-btn" @click="updateLabel('spam')">Spam</button>
			<button class="btn btn-danger input-group-btn" @click="updateLabel('negative')">Negative</button>
			<button class="btn btn-neutral input-group-btn" @click="updateLabel('neutral')">Neutral</button>
			<button class="btn btn-success input-group-btn" @click="updateLabel('positive')">Positive</button>
			<button class="btn btn-info input-group-btn" @click="refresh()"><i class="fa fa-refresh" aria-hidden="true"></i></button>
		</div>
		<textarea class="form-input" rows="2" readonly>{{feedback}}</textarea>
	</div>
</template>

<script>

module.exports = {
	name: 'live-clasifier',
	created () {
	},
	computed: {
		category () {
			return this.$store.state.randomFeedback.name
		},
		feedback () {
			return this.$store.state.randomFeedback.feedback
		}
	},
	methods: {
		updateLabel ( label ) {
			console.log( label )
			this.$store.dispatch( 'updateLabel', { label: label } ).then( this.$store.dispatch( 'fetchRandomFeedback' ) )
		},
		refresh () {
			this.$store.dispatch( 'fetchRandomFeedback' )
		}
	},
	components: {
	}
}
</script>