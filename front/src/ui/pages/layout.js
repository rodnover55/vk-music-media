import React from 'react'
import { Link, IndexLink } from 'react-router'

import Player from '../components/player/player'

export default class Layout extends React.Component {

    render() {
        return (
            <div className="layout">
                <Player />
                <nav className="layoutNav">
                    <IndexLink to="/" className="layoutNavLink" activeClassName={"__active"}>New</IndexLink>
                    <Link to="/tags" className="layoutNavLink" activeClassName={"__active"}>Tags</Link>
                </nav>
                {this.props.children}
            </div>
        )
    }
}