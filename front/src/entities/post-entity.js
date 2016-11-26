import Entity from '../lib/entity';
import TrackEntity from './track-entity';

export default class PostEntity extends Entity {

    static parsers = {
        tracks(list) {
            return list.map(item => Entity.make(TrackEntity, item))
        }
    };

    id = 0;
    created_at = '1970-01-01';
    title = '';
    image = '';
    description = '';
    tags = [];
    tracks = [];
}