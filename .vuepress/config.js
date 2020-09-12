module.exports = {
    title: "Laravel PayU",
    description: "PayU Payment Gateway Integration for Laravel",
    base: '/',

    head: [
        [
            'link',
            {
                href:
                    'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i',
                rel: 'stylesheet',
                type: 'text/css',
            },
        ],
    ],

    themeConfig: {
        siteName: 'Laravel PayU',
        displayAllHeaders: true,
        activeHeaderLinks: false,
        searchPlaceholder: 'Press / to search',
        lastUpdated: false,
        sidebarDepth: 0,

        repo: 'tzsk/payu',

        docsRepo: 'tzsk/payu',
        editLinks: true,
        editLinkText: 'Help improve this page!',

        nav: [
            { text: 'Home', link: '/', target: '_self' },
        ],

        sidebar: {
            '/5.x/': require('./5.x')
        },
    },

    plugins: [
        [
            '@vuepress/pwa',
            {
                serviceWorker: true,
                updatePopup: true
            }
        ]
    ],
}
