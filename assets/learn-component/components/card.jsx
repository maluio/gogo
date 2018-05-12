import {HtmlRaw} from "./util";

// highlight js
let highlightJs = require('../../lib/highlightJs/highlightJs.min');
//require('../../lib/highlightJs/highlightJs-solarized-dark.css');

export class Card extends React.Component {

    componentDidMount() {
        this.highlightCode();
    }
    componentDidUpdate() {
        this.highlightCode();
    }

    highlightCode() {
        const domNode = ReactDOM.findDOMNode(this);
        const nodes = domNode.querySelectorAll('pre code');
        nodes.forEach((block) => highlightJs.highlightBlock(block));
    }

    render() {
        return (
            <div>
                <div className="card card-outline-secondary mb-3">
                    <div className="card-body">
                        <HtmlRaw raw={this.props.content}/>
                    </div>
                </div>
            </div>
        )
    }
}