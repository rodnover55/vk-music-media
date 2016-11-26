import React from 'react'
import { Link } from 'react-router';

export default class Tag extends React.Component {

    get tagName() {
        return this.props.tag
    }

    get tagUrl() {
        return {pathname: '/tags', query: {tags: this.props.tag}};
    }

    render () {
        return (
            <Link className="tag" to={this.tagUrl}>#{this.tagName}</Link>
        )
    }
}