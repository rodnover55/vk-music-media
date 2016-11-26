import React from 'react'

import Tag from '../tag/tag';

export default class Post extends React.Component {

    render() {
        return (
            <article className="post">
                <img className="postImage" src={this.props.image} alt=""/>
                <div className="postDescription __collapsed"></div>
                <div className="postTags">
                    {this.props.tags.map(tag => <Tag tag={tag} />)}
                </div>
            </article>
        )
    }
}