export class OrganizationSignUp{
	constructor(
		public organizationAddressCity: string,
		public organizationAddressState: string,
		public organizationAddressStreet: string,
		public organizationAddressZip: string,
		public organizationDonationAccepted: string,
		public organizationEmail: string,
		public organizationHoursOpen: string,
		public organizationName: string,
		public organizationPassword: string,
		public organizationPasswordConfirm: string,
		public organizationPhone: string,
		public organizationUrl: string
	) {}
}