// react get loaded in the twig template, because you don't get those insane file sizes from webpack during devlopment

//let React = require('react');
//let ReactDOM = require('react-dom');


import {ImageManager} from "./imageManager";


class Admin extends React.Component {

    constructor() {
        let data = document.getElementById('item_data').value;

        super();
        this.state = {
            data: JSON.parse(data),
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

    render() {
        return (
            <div id="admin-view">
                <ImageManager
                    images={this.state.data.images}
                    updateImages={(images) => this.updateImages(images)}
                />
            </div>
        )
    }
}

ReactDOM.render(<Admin/>, document.getElementById("admin-jsx"));