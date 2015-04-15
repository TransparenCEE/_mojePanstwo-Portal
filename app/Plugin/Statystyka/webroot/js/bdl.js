String.prototype.capitalizeFirstLetter = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

(function ($) {
    var tree = $('#tree'),
        json = tree.data('structure'),
        rootData = [];

    $.each(json, function (key, data) {
        var root = {
                "text": key.toLowerCase().capitalizeFirstLetter()
            },
            rootChildren = [];

        $.each(data, function (itemKey, itemData) {
            var item = {
                'data ': {
                    id: itemKey
                },
                'text': itemData['dane']['tytul'].toLowerCase().capitalizeFirstLetter(),
                'children': true
            }, itemChildren = [];

            $.each(itemData['grupy'], function (grupyKey, grupyData) {
                var grupy = {
                    'data': {
                        'id': grupyKey
                    },
                    'text': grupyData['dane']['tytul'].toLowerCase().capitalizeFirstLetter(),
                    'children': true
                }, grupyChildren = [];

                $.each(grupyData['podgrupy'], function (podgrupyKey, podgrupyData) {
                    var podgrupy = {
                        data: {
                            'id': podgrupyKey
                        },
                        'text': podgrupyData['dane']['tytul'].toLowerCase().capitalizeFirstLetter()
                    };
                    grupyChildren.push(podgrupy);
                });

                grupy.children = grupyChildren;
                itemChildren.push(grupy);
            });

            item.children = itemChildren;
            rootChildren.push(item);
        });

        root.children = rootChildren;
        rootData.push(root)
    });

    tree.jstree({
        'core': {
            'data': rootData
        }
    });
}(jQuery));