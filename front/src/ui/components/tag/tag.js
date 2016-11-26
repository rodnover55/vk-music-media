import React from 'react'

export default class Tag extends React.Component {

    get tagName() {
        return this.props.tag
    }

    get tagUrl() {
        return `/tags/${this.props.tag}`
    }

    render () {
        return (
            <Link className="tag" to={this.tagUrl}>{this.tagName}</Link>
        )
    }
}