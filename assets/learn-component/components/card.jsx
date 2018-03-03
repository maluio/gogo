import {HtmlRaw} from "./util";

export class Card extends React.Component {
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