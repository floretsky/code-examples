// foreach analog

 function forEach(array, fn) {
    for (let i = 0; i < array.length; i++) {
        fn(array[i], i, array);
    }
}

// map analog 

function map(array, fn) {
    const result = [];

    for (let i = 0; i < array.length; i++) {
        result.push(fn(array[i], i, array));
    }

    return result;
}

// reduce analog

function reduce(array, fn, initial) {
    var index = 0; 

    if (!initial) {
        initial = array[0];
        index = 1;
    }

    for (let i = index; i < array.length; i++) {
        initial = fn(initial, array[i], i, array);
    }

    return initial;
}

// upperProps({ name: 'Anton', lastName: 'Floretskii' }) returns ['NAME', 'LASTNAME']

function upperProps(obj) {
    const newArray = [];

    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            newArray.push(key.toUpperCase());
        }
    }

    return newArray;
}

//slice analog

function slice(array, from = 0, to = array.length) {
    const newArray = [], 
        absoluteFrom = Math.abs(from), 
        absoluteTo = Math.abs(to);

    if (from < 0) {
        absoluteFrom < array.length ? from = array.length + from : from = 0
    } else if (absoluteFrom > array.length) {
        absoluteFrom < array.length ? from = 0 : from = array.length
    }

    if (to < 0) {
        to = array.length + to;
    } else if (absoluteTo > array.length) {
        to = array.length
    }

    if (from < to) {
        for (let i = from; i < to; i++) {
            newArray.push(array[i]);
        }
    }

    return newArray;
}

/* let array = [1, 2, 3, 4, 5, 6, 7];
console.log(slice(array,9999));  */

function createProxy(obj) {
    let handler = {
        set: function(target, name) {
            return target[name] * target[name];
        }
    };

    var newObject = new Proxy(obj, handler);

    return newObject;
}

export {
    forEach,
    map,
    reduce,
    upperProps,
    slice,
    createProxy
};