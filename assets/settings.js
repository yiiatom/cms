(function () {
    'use strict';

    function init() {
        const list = document.querySelector('.sortable-settings');
        if (!list) return;

        const url = list.dataset.sortUrl;
        if (!url) return;

        function getChildRows(parent) {
            const rows = [];
            const type = parent.dataset.type;

            if (type === 'group') {
                let next = parent.nextElementSibling;
                while (next && (next.dataset.type !== 'group')) {
                    rows.push(next);
                    next = next.nextElementSibling;
                }
            }

            return rows;
        }

        function getNextVisible(item) {
            let next = item.nextElementSibling;
            while (next && next.classList.contains('d-none')) {
                next = next.nextElementSibling;
            }
            return next;
        }

        let draggedChildren = [];

        new Sortable(list, {
            handle: '.sort-handle',
            animation: 150,
            ghostClass: 'table-active',
            onStart: function (evt) {
                const row = evt.item;

                draggedChildren = getChildRows(row);
                draggedChildren.forEach(child => child.classList.add('d-none'));
            },
            onMove: function (evt) {
                const type = evt.dragged.dataset.type;
                const related = evt.related;

                if (evt.willInsertAfter) {
                    const next = getNextVisible(related);
                    if ((type === 'group') && next && (next.dataset.type !== 'group')) {
                        return false;
                    }
                } else {
                    if (type === 'group') {
                        if ((related.dataset.type !== 'group')) {
                            return false;
                        }
                    } else {
                        if (!related.previousElementSibling) {
                            return false;
                        }
                    }
                }

                return true;
            },
            onEnd: function (evt) {
                const target = evt.item;

                let item = target;
                draggedChildren.forEach(child => {
                    item.after(child);
                    item = child;
                    child.classList.remove('d-none')
                });

                draggedChildren = [];

                const type = target.dataset.type;
                const data = {
                    type: type,
                    positions: {},
                };

                if (type === 'group') {
                    let pos = 1;
                    let item = target.parentElement.firstElementChild;
                    while (item) {
                        if (item.dataset.type === 'group') {
                            data.positions[item.dataset.uuid] = pos;
                            pos++;
                        }
                        item = item.nextElementSibling;
                    }
                } else {
                    let group = target.previousElementSibling;
                    while (group && (group.dataset.type !== 'group')) {
                        group = group.previousElementSibling;
                    }
                    data.groupUuid = group.dataset.uuid;
                    let pos = 1;
                    let item = group.nextElementSibling;
                    while (item && (item.dataset.type !== 'group')) {
                        data.positions[item.dataset.uuid] = pos;
                        pos++;
                        item = item.nextElementSibling;
                    }
                }

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf"]')?.content,
                    },
                    body: JSON.stringify(data),
                }).catch(err => console.error('Sort Error:', err));
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => init());
    } else {
        init();
    }
})();





document.addEventListener('DOMContentLoaded', function () {
    return;

    const list = document.querySelector('.sortable-tree');
    if (!list) return;

    const url = list.dataset.sortUrl;
    if (!url) return;

    function getChildRows(parent) {
        const rows = [];
        const depth = parseInt(parent.dataset.depth);

        let next = parent.nextElementSibling;
        while (next && parseInt(next.dataset.depth) > depth) {
            rows.push(next);
            next = next.nextElementSibling;
        }

        return rows;
    }

    let draggedChildren = [];
    let allowMin = null;
    let allowMax = null;

    new Sortable(list, {
        handle: '.sort-handle',
        animation: 150,
        ghostClass: 'table-active',
        onStart: function (evt) {
            const row = evt.item;
            const depth = parseInt(row.dataset.depth);

            draggedChildren = getChildRows(row);
            draggedChildren.forEach(child => child.classList.add('d-none'));

            allowMin = null;
            let prev = row.previousElementSibling;
            while (prev) {
                if (parseInt(prev.dataset.depth) < depth) {
                    allowMin = prev;
                    break;
                }
                prev = prev.previousElementSibling;
            }

            allowMax = null;
            let next = row.nextElementSibling;
            while (next && draggedChildren.includes(next)) {
                next = next.nextElementSibling;
            }
            while (next) {
                if (parseInt(next.dataset.depth) < depth) {
                    allowMax = next;
                    break;
                }
                next = next.nextElementSibling;
            }
        },
        onMove: function (evt) {
            const depth = parseInt(evt.dragged.dataset.depth);
            const related = evt.related;
            const relatedDepth = parseInt(related.dataset.depth);

            if (relatedDepth < depth) {
                return false;
            }
            if (evt.willInsertAfter) {
                const next = related.nextElementSibling;
                if (next && parseInt(next.dataset.depth) > depth) {
                    return false;
                }
            } else {
                if (relatedDepth !== depth) {
                    return false;
                }
            }
            if (allowMin && related.rowIndex <= allowMin.rowIndex) {
                return false;
            }
            if (allowMax && related.rowIndex >= allowMax.rowIndex) {
                return false;
            }

            return true;
        },
        onEnd: function (evt) {
            const target = evt.item;
            const depth = parseInt(target.dataset.depth);

            let item = target;
            draggedChildren.forEach(child => {
                item.after(child);
                item = child;
                child.classList.remove('d-none')
            });

            draggedChildren = [];

            let first = target;
            while (first.previousElementSibling) {
                if (parseInt(first.previousElementSibling.dataset.depth) < depth) {
                    break;
                }
                first = first.previousElementSibling;
            }

            const positions = {};
            let pos = 1;
            item = first;
            while (item) {
                const itemDepth = parseInt(item.dataset.depth);
                if (itemDepth < depth) {
                    break;
                } else if (itemDepth === depth) {
                    positions[item.dataset.uuid] = pos;
                    pos++;
                }
                item = item.nextElementSibling;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf"]')?.content,
                },
                body: JSON.stringify(positions),
            }).catch(err => console.error('Sort Error:', err));
        }
    });
});
