// react get loaded in the twig template, because you don't get those insane file sizes from webpack during devlopment

//let React = require('react');
//let ReactDOM = require('react-dom');
require('./admin.scss');


import {ImageManager} from "./imageManager";
import {Words} from "./words";


class Admin extends React.Component {

    constructor() {
        let data = document.getElementById('item_data').value;
        data = JSON.parse(data);
        if(!data){
            data = {
                images:[],
                words: []
            }
        }
        super();
        this.state = {
            data: data,
            searchTerm: '',
            newImages: []
        };
    }

    updateData(data){
        this.setState({data: data});
        document.getElementById('item_data').value = JSON.stringify(data);
    }

    updateImages(images){
        let data = this.state.data;
        data.images = images;
        this.updateData(data)
    }

    updateWords(words){
        let data = this.state.data;
        data.words = words;
        this.updateData(data)
    }

    render() {
        return (
            <div id="admin-view">
                <Words
                    words={this.state.data.words}
                    updateWords={(words) => this.updateWords(words)}
                />
                <ImageManager
                    images={this.state.data.images}
                    updateImages={(images) => this.updateImages(images)}
                />
            </div>
        )
    }
}

ReactDOM.render(<Admin/>, document.getElementById("admin-jsx"));