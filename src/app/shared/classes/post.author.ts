export class PostAuthor {
	constructor(
		public id: number,
		public postOrganizationId: number,
		public postContent: string,
		public postEndDateTime: any,
		public postImageUrl: string,
		public postStartDateTime: any,
		public postTitle: string,
		public postWriter: string
	) {}
}