import CommonObject from './common-object';

export default class Entity extends CommonObject {
    static make(entityClass, attributes) {
        const inst = new entityClass;
        const parsers = entityClass.parsers || {};

        for (const [key, value] of Object.entries(attributes)) {
            if (inst.hasOwnProperty(key)) {
                if (typeof parsers[key] === 'function') {
                    inst[key] = parsers[key](value);
                } else {
                    inst[key] = value;
                }
            }
        }

        return Object.freeze(inst);
    }

    *[Symbol.iterator]() {
        for (const propName of this) {
            if (this.hasOwnProperty(propName)) {
                yield [propName, this[propName]]
            }
        }
    }
}