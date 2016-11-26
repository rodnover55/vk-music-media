import React from 'react'

import Post from '../post/post'
import di from '../../../di';

export default class PostList extends React.Component {

    state = {
        posts: []
    };

    /** @member PostService */
    postService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.postService = di.container.PostService
    }

    componentDidMount() {
        this.postService.getRecentPosts().then((posts) => {
            this.setState({posts})
        })
    }

    render() {
        return (
            <div className="postList">
                {this.state.posts.map(post => <Post {...post} />)}
            </div>
        )
    }
}