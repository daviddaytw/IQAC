
export function ScoreCell({ score }: { score: number }) {
    const G = Math.round(score <= 50 ? score * 5.1 : 255 )
    const R = Math.round(score >= 50 ? (score-50 * 5.1) : 255 )

    console.log(G, R)

    return (
        <td style={{
            backgroundColor: `rgb(${R}, ${G}, 0)`
        }}>
            {score}
        </td>
    )
}