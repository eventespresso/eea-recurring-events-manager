import { __ } from '@eventespresso/i18n';

export const PATTERN_TYPE_RECURRENCE = 'recurrence';

export const PATTERN_TYPE_EXCLUSION = 'exclusion';

export const RRULE_FREQUENCIES = {
	DAILY: 'DAILY',
	WEEKLY: 'WEEKLY',
	MONTHLY: 'MONTHLY',
};

export const RRULE_FREQUENCY_LABELS = {
	DAILY: __( 'Daily', 'event_espresso' ),
	WEEKLY: __( 'Weekly', 'event_espresso' ),
	MONTHLY: __( 'Monthly', 'event_espresso' ),
};

export const RRULE_DAYS = {
	MO: 'MO',
	TU: 'TU',
	WE: 'WE',
	TH: 'TH',
	FR: 'FR',
	SA: 'SA',
	SU: 'SU',
};

export const RRULE_DAY_LABELS = {
	MO: __( 'Monday', 'event_espresso' ),
	TU: __( 'Tuesday', 'event_espresso' ),
	WE: __( 'Wednesday', 'event_espresso' ),
	TH: __( 'Thursday', 'event_espresso' ),
	FR: __( 'Friday', 'event_espresso' ),
	SA: __( 'Saturday', 'event_espresso' ),
	SU: __( 'Sunday', 'event_espresso' ),
};
