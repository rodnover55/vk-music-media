import React from 'react'

import di from '../../../di';

import Tag from '../tag/tag';

export default class TagList extends React.Component {

    state = {
        tags: []
    };

    /** @member TagService */
    tagService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.tagService = di.container.TagService;
    }

    componentDidMount() {
        this.tagService.getTags().then(tags => this.setState({tags}))
    }

    getAddClass(tag) {
        if (this.props.tags.indexOf(tag) === -1) {
            return ''
        }

        return '__active'
    }

    render() {
        return (
            <div className="tagList">
                {this.state.tags.map((tag, index) => {
                    return (
                        <Tag
                            multiple={true}
                            tags={this.props.tags}
                            addClass={this.getAddClass(tag)}
                            key={index}
                            tag={tag}/>
                    )
                })}
            </div>
        )
    }
}