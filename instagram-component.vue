<template>
	<div :class="componentClass">
		<slot v-for="(feed, index) in feeds" name="feeds" :index="index" :feed="feed"></slot>
		<slot name="error" :error="error"></slot>
	</div>
</template>

<script>
	// Import lodash library
	import _ from 'lodash'
	// Import jsonp library
	import jsonp from 'browser-jsonp'

	export default {
		name: 'instagram-component',

		props: {
			/*
			* Instagram access token.
			*/
			token: {
				type: String,
				required: true
			},

			/*
			* Number of recent posts.
			*/
			count: {
				type: Number,
				default: 3,
				required: false
			},

			/*
			* Filter by media type, ex: video, image.
			*/
			mediaType: {
				type: String,
				required: false
			},

			/*
			* Filter by tags.
			*/
			tags: {
				type: Array,
				default: () => [],
				required: false
			},

			// Class for container div
			componentClass: {
				type: String,
				default : '',
				required : false
			}
		},

		// Set error and feed array to accept incoming api data
		data: () => ({
			error: '',
			feeds: []
		}),

		// Mount the user feed.
		mounted () {
			this.getUserFeed()
		},

		methods: {
			// Get User Feed
			getUserFeed () {
				jsonp({
					url: `https://api.instagram.com/v1/users/self/media/recent`,
					data: { access_token: this.token, count: this.count },
					// If request returns errors, return error.
					error: error => { throw error },
					// If request is valid
					complete: response => {
						// If error 400
						if (response.meta.code === 400) this.error = response.meta
						// If error is oK
						if (response.meta.code === 200) {
							// Bind response to data
							let { data } = response
							// Set variable to types of media returned
							const types = ['image', 'video']
							// If media type is in post
							if (this.mediaType && types.indexOf(this.mediaType) > -1) {
								// Set data
								data = _.filter(data, item => this.mediaType === item.type)
							}

							// If there are tags in post
							if (this.tags.length) {
								// Set tags
								data = _.filter(data, item => _.intersection(this.tags, item.tags).length)
							}
							// Slice feed data for vue component for number of posts
							this.feeds = _.slice(_.values(data), 0, this.count)
						}
					}
				})
			}
		}
	}
</script>
