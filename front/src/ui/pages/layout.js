import React from 'react'
import { Link, IndexLink } from 'react-router'

import di from '../../di';

import Player from '../components/player/player'

export default class Layout extends React.Component {

    state = {
        refreshDisabled: false
    };

    /** @member PostService */
    postService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.postService = di.container.PostService;
    }

    async fetchPosts()
    {
        this.setState({refreshDisabled: true});
        try {
            await this.postService.refreshPosts();
        } catch (e) {
            alert(e.body.message);
        } finally {
            this.setState({refreshDisabled: false});
        }
    }

    onRefreshClick()
    {
        this.fetchPosts();
    }

    render() {
        return (
            <div className="layout">
                <Player />
                <nav className="layoutNav">
                    <IndexLink to="/" className="layoutNavLink" activeClassName={"__active"}>
                        Свежие плейлисты
                    </IndexLink>
                    <Link to="/tags" className="layoutNavLink" activeClassName={"__active"}>
                        Поиск по тегам
                    </Link>
                </nav>
                <button style={{display: 'none'}} disabled={this.state.refreshDisabled} onClick={()=>this.onRefreshClick()}>
                    Обновить посты
                </button>
                {this.props.children}
            </div>
        )
    }
}