export class PostAuthor {
	constructor(
		public id: number,
		public postOrganizationId: number,
		public postContent: string,
		public postEndDateTime: Date,
		public postImageUrl: string,
		public postStartDateTime: Date,
		public postTitle: string,
		public postWriter: string
	) {}
}