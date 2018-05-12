// react get loaded in the twig template, because you don't get those insane file sizes from webpack during devlopment

//let React = require('react');
//let ReactDOM = require('react-dom');
require('./admin.scss');


import {ImageManager} from "./imageManager";
import {Words} from "./words";
import {MainWord} from "./mainWord";
import {Phrases} from "./phrases";


class Admin extends React.Component {

    constructor() {
        let data = document.getElementById('item_data').value;
        data = JSON.parse(data);
        if(!data){
            data = {
                images:[],
                words: [],
                phrases: [],
                mainWord :
                    {
                        lemma: '',
                        forms: [],
                        gender: ''
                    }
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
        this.setState((prevState, props) => {
            return {
                data: data
            }
        });
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

    updatePhrases(phrases){
        let data = this.state.data;
        data.phrases = phrases;
        this.updateData(data)
    }

    updateMainWord(mainWord){
        let data = this.state.data;
        data.mainWord = mainWord;
        this.updateData(data)
    }

    render() {
        return (
            <div id="admin-view">
                <MainWord
                    mainWord={this.state.data.mainWord}
                    updateMainWord={(word) => this.updateMainWord(word)}
                />
                <Words
                    words={this.state.data.words}
                    updateWords={(words) => this.updateWords(words)}
                    term={this.state.data.mainWord.lemma}
                />
                <Phrases
                    phrases={this.state.data.phrases}
                    updatePhrases={(phrases) => this.updatePhrases(phrases)}
                />
                <ImageManager
                    images={this.state.data.images}
                    updateImages={(images) => this.updateImages(images)}
                    term={this.state.data.mainWord.lemma}
                />
            </div>
        )
    }
}

ReactDOM.render(<Admin/>, document.getElementById("admin-jsx"));