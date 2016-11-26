import React from 'react'
import { render } from 'react-dom'
import { Router, Route, IndexRoute, Link, browserHistory } from 'react-router'

import Layout from './pages/layout';
import Main from './pages/main';
import Tags from './pages/tags';
import NotFound from './pages/not-found';

render((
    <Router history={browserHistory}>
        <Route path="/" component={Layout}>
            <IndexRoute component={Main}/>
            <Route path="tags" component={Tags}/>
            <Route path="*" component={NotFound}/>
        </Route>
    </Router>
), document.getElementById('react-app'));