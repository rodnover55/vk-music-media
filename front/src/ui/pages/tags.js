import React from 'react'
import { Link } from 'react-router'

import TagList from '../components/tag-list/tag-list';
import PostList from '../components/post-list/post-list';

export default class NotFound extends React.Component {

    render() {
        const tagString = this.props.location.query.tags || '';
        let tagList = [];

        if (tagString !== '') {
            tagList = tagString.split(',');
        }

        return (
            <section className="page">
                <TagList tags={tagList}/>
                <PostList/>
            </section>
        )
    }
}