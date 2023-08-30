// @ts-check
import { array, boolean, integer, nullType, nullable, number, object, optional, parse, string, url } from "valibot";
import { request } from "../../../js/api/api";

/**
 * @template {import("valibot").BaseSchema<any, any>} T
 * @param {T} data
 * @returns
 */
const ApiResponse = (data) => object({
    data,
});

const UserSchema = object({
    id: number([integer()]),
    displayName: string(),
    username: string(),
    profilePicture: string([url()]),
    trainDistance: number(),
    trainSpeed: number(),
    points: number([integer()]),
    twitterUrl: nullType(),
    mastodonUrl: nullable(string([url()])),
    privateProfile: boolean(),
    preventIndex: boolean(),
    likes_enabled: boolean(),
});

const GroupSchema = object({
    id: number(),
    name: string(),
    description: optional(string()),
    inactivityHours: number(),
    members: array(UserSchema),
    owner: UserSchema,
});

/**
 * @typedef {import("valibot").Output<typeof GroupSchema>} Group
 */

/**
 * Creates a new group.
 * @param {string} name the name of the group
 * @param {string|null} description the description of the group
 * @param {number} inactivityHours hours after which the group automatically gets closed
 * @returns {Promise<Group>} the created group.
 */
export async function createGroup(name, description, inactivityHours) {
    const group = parse(ApiResponse(GroupSchema), await request('group', {
        method: 'POST',
        body: {
            name,
            description,
            inactivityHours,
        }
    }));
    return group.data;
}
