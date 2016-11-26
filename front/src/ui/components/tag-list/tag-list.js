import React from 'react'

import Tag from '../tag/tag';

export default class TagList extends React.Component {

    get tags() {
        return []
    }

    render() {
        return (
            <div className="tagList">
                {this.tags.map(tag => <Tag tag={tag}/>)}
            </div>
        )
    }
}