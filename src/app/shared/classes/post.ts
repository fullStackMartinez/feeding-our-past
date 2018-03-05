export class Post {
	constructor(
		public id: number,
		public postOrganizationId: number,
		public postContent: string,
		public postEndDateTime: Date,
		public postImageUrl: string,
		public postStartDateTime: Date,
		public postTitle: string
	) {}
}