import React from 'react'
import { Link } from 'react-router';

export default class Tag extends React.Component {

    get tagName() {
        return this.props.tag
    }

    get tagUrl() {
        if (this.props.multiple === undefined) {
            return {pathname: '/tags', query: {
                tags: this.props.tag
            }};
        }

        if (this.props.tags.indexOf(this.props.tag) === -1) {
            return {pathname: '/tags', query: {
                tags: this.props.tags.concat([this.props.tag]).join(',')
            }};
        }

        return {pathname: '/tags', query: {
            tags: this.props.tags.filter(tag => tag !== this.props.tag).join(',')
        }};
    }

    render () {
        return (
            <Link
                className={this.props.addClass + ' tag'}
                to={this.tagUrl}>#{this.tagName}
            </Link>
        )
    }
}