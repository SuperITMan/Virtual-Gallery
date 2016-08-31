"user strict";

export interface INews {
    id:number;
    title:string;
    content:string;
    creationDate:string;
}

export class News implements INews {
    public id:number;
    public title:string;
    public content:string;
    public creationDate:string;

    public constructor (news:INews) {
        this.id = news.id;
        this.title = news.title;
        this.content = news.content;
        this.creationDate = news.creationDate;
    }
}