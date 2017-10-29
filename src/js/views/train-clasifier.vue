<template>
	<div class="col-12 text-comfortaa">
		<h2 class="text-comfortaa text-center p-2"><i class="fa fa-connectdevelop" aria-hidden="true"></i> Train Data</h2>
		<p class="text-comfortaa text-left text-justify p-2">
			<small>
				<i class="fa fa-info-circle" aria-hidden="true"></i> <b>Notes:</b>
				<i>Use the listed data to train Lala. You can view all the labeled data bellow, or click "Train".</i><br/>
				<b>Current Accuracy Level: </b>{{accuracy}}<br/>
				<b>Precision per. label: </b><br/>
				{{precision_per_label}}
			</small>
		</p>
		<button class="btn btn-block btn-success" @click="trainAi"><i class="fa fa-connectdevelop" aria-hidden="true"></i> Train A.I.</button>
		<table class="table table-striped table-hover filter">
			<input type="radio" id="tag-0" class="filter-tag" name="filter-radio" hidden checked>
			<input type="radio" id="tag-1" class="filter-tag" name="filter-radio" hidden>
			<input type="radio" id="tag-2" class="filter-tag" name="filter-radio" hidden>
			<input type="radio" id="tag-3" class="filter-tag" name="filter-radio" hidden>
			<input type="radio" id="tag-4" class="filter-tag" name="filter-radio" hidden>
			<thead>
				<tr>
					<th colspan="2">
						<div class="filter-nav">
							<b>Filters: </b>
							<label class="chip bg-info text-light" for="tag-0">All</label>
							<label class="chip bg-warning text-light" for="tag-1">Spam</label>
							<label class="chip bg-error text-light" for="tag-2">Negative</label>
							<label class="chip bg-neutral text-light" for="tag-3">Neutral</label>
							<label class="chip bg-success text-light" for="tag-4">Positive</label>
						</div>
					</th>
					<th>
						<button class="btn btn-info btn-sm" @click="refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Table</button>
					</th>
				</tr>
				<tr>
					<th>Category</th>
					<th>Feedback</th>
					<th>Label</th>
				</tr>
			</thead>
			<tbody class="filter-body" style="display: table-row-group;">
				<tr class="filter-item" v-bind:data-tag="tagData( item.tag )" v-for=" item in tableData ">
					<td>{{item.category}}</td>
					<td>{{item.feedback}}</td>
					<td><label class="chip text-light" :class="labelBg( item.tag )">{{item.label}}</label></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th>Category</th>
					<th>Feedback</th>
					<th>Label</th>
				</tr>
				<tr>
					<th colspan="3">
						<div class="filter-nav">
							<b>Filters: </b>
							<label class="chip bg-info text-light" for="tag-0">All</label>
							<label class="chip bg-warning text-light" for="tag-1">Spam</label>
							<label class="chip bg-error text-light" for="tag-2">Negative</label>
							<label class="chip bg-neutral text-light" for="tag-3">Neutral</label>
							<label class="chip bg-success text-light" for="tag-4">Positive</label>
						</div>
					</th>
				</tr>
			</tfoot>
		</table>
		<p class="text-comfortaa text-left text-justify p-2">
			<small>
				<i class="fa fa-info-circle" aria-hidden="true"></i> <b>Notes:</b>
				<i>The table only lists 25 random samples of labeled data. During training 1000 samples are pooled.</i><br/>
			</small>
		</p>
	</div>
</template>
<script>
function capitalizeFirstLetter ( string ) {
	return string.charAt( 0 ).toUpperCase() + string.slice( 1 )
}

module.exports = {
	name: 'train-clasifier',
	created () {
	},
	computed: {
		accuracy: function () {
			let currentAccuracy = '0.00%'

			if ( this.$store.state.accuracy.accuracy ) {
				currentAccuracy = ( this.$store.state.accuracy.accuracy * 100 ).toFixed( 2 ) + '%'
			}
			return currentAccuracy
		},
		precision_per_label: function () {
			let precisionText = 'N/A'

			if ( this.$store.state.accuracy.precision ) {
				precisionText = ''
				for ( let label in this.$store.state.accuracy.precision ) {
					precisionText += capitalizeFirstLetter( label ) + ': ' + ( this.$store.state.accuracy.precision[label] * 100 ).toFixed( 2 ) + '%' + ' | '
				}
			}

			return precisionText
		},
		tableData: function () {
			let table = []
			let trainingTable = this.$store.state.trainingTable
			console.log( this.$store.state.trainingTable )
			for ( let index in trainingTable ) {
				let tag = 1
				if ( trainingTable[index][2] === 'negative' ) tag = 2
				if ( trainingTable[index][2] === 'neutral' ) tag = 3
				if ( trainingTable[index][2] === 'positive' ) tag = 4

				let item = {
					'category': trainingTable[index][0],
					'feedback': trainingTable[index][1],
					'label': capitalizeFirstLetter( trainingTable[index][2] ),
					'tag': tag
				}

				table.push( item )
			}

			return table
		}
	},
	methods: {
		tagData: function ( tag ) {
			if ( tag === 1 ) return 'tag-1'
			if ( tag === 2 ) return 'tag-2'
			if ( tag === 3 ) return 'tag-3'
			if ( tag === 4 ) return 'tag-4'
			return 'tag-1'
		},
		labelBg: function ( tag ) {
			return {
				'bg-warning': tag === 1,
				'bg-error': tag === 2,
				'bg-neutral': tag === 3,
				'bg-success': tag === 4
			}
		},
		trainAi: function () {
			this.$store.dispatch( 'newTraining' )
		},
		refresh: function () {
			this.$store.dispatch( 'fetchTrainingData' )
		}
	},
	components: {
	}
}
</script>