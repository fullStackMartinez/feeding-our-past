export class Organization {
	constructor(
		public id: string,
		public distance: string,
		public organizationAddressCity: string,
		public organizationAddressState: string,
		public organizationAddressStreet: string,
		public organizationAddressZip: string,
		public organizationDonationAccepted: string,
		public organizationEmail: string,
		public organizationHoursOpen: string,
		public organizationName: string,
		public organizationPhone: string,
		public organizationUrl: string,
		public organizationLatX: number,
		public organizationLongY: number
	) {}
}