import CommonObject from './common-object';

export default class Entity extends CommonObject {
    static make(entityClass, attributes) {
        const inst = new entityClass;

        for (const [key, value] of Object.entries(attributes)) {
            if (!inst.hasOwnProperty(key)) {
                throw new TypeError(entityClass.name + ' don`t have property ' + key)
            }
            inst[key] = value;
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