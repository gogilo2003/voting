export class cNotification {
    success: string = null;
    warning: string = null;
    danger: string = null;
    info: string = null;
}

export class cElection {
    id: number;
    title: string = "";
    description: string = "";
    start: string = null;
    end: string = null;
    positions: Array<cPosition>
}

export class cPosition {
    id: number;
    name: string = "";
    description: string = "";
    candidates: Array<cMember>
}

export class cMember {
    id: number;
    name: string = "";
    email: string = "";
    phone: string = "";
    photo: string = "";
}
