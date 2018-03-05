export class Organization {
	constructor(
		public id: string,
		public distance: string,
		public organizationDonationAccepted: string,
		public organizationEmail: string,
		public organizationHoursOpen: string,
		public organizationName: string,
		public organizationPhone: string,
		public organizationUrl: string,
		public userLatX: number,
		public userLongY: number
	) {}
}