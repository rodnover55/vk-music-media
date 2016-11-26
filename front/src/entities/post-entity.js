import Entity from '../lib/entity';

export default class PostEntity extends Entity {
    id = 0;
    created_at = '1970-01-01';
    title = '';
    image = '';
    description = '';
    tags = [];
    tracks = [];
}