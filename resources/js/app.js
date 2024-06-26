import './bootstrap';

import CountdownTimer from '@10up/countdown-timer';

new CountdownTimer('.countdown-timer', {
    allowNegative: false,
    compact: false,
    padValues: false,
    separator: ', ',
    showZeroes: false,
    years: {
        allowed: true,
        singular: 'year',
        plural: 'years'
    },
    weeks: {
        allowed: true,
        singular: 'week',
        plural: 'weeks'
    },
    days: {
        allowed: true,
        singular: 'day',
        plural: 'days'
    },
    hours: {
        allowed: true,
        singular: 'hour',
        plural: 'hours'
    },
    minutes: {
        allowed: true,
        singular: 'minute',
        plural: 'minutes'
    },
    seconds: {
        allowed: true,
        singular: 'second',
        plural: 'seconds'
    }
});
