export default class CommonObject {
    static make(constr, ...args) {
        return Object.seal(new constr(...args));
    }

    get [Symbol.toStringTag]() {
        return 'CommonObject';
    }

    [Symbol.toPrimitive]() {
        throw new TypeError(`${this[Symbol.toStringTag]} cannot be converted to primitive`);
    }
}