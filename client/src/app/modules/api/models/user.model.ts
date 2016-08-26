"user strict";

export interface IUser {
    id:number;
    username:string;
}

export class User implements IUser {
    public id:number;
    public username:string;

    public constructor (user:IUser) {
        this.id = user.id;
        this.username = user.username;
    }
}