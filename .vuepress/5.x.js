module.exports = [
    {
        title: "Getting Started",
        collapsable: false,
        children: [
            'introduction',
            'installation',
            'configuration',
            'changes',
            'upgrade',
        ],
    }, {
        title: "Features",
        collapsable: false,
        children: prefix('features', [
            'payment',
            'concerns',
            'gateways',
            'relationship',
            'verification',
            'events',
        ]),
    },
]

function prefix(prefix, children) {
    return children.map(child => `${prefix}/${child}`)
}
