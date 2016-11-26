import Bottle from 'bottlejs';

import AuthService from './services/auth-service';
import BasicService from './services/basic-service';
import PostService from './services/post-service';

const bottle = new Bottle;

bottle.service('AuthService', AuthService);
bottle.service('BasicService', BasicService, 'AuthService');
bottle.service('PostService', PostService, 'AuthService');

export default bottle;