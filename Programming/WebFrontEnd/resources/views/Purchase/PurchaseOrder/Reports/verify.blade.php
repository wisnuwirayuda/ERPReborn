<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify PO</title>
    <style>
        :root {
            --page-bg: #f7f5f0;
            --text: #152238;
            --muted: #687384;
            --border: #e7e5df;
            --input-border: #d9d6ce;
            --primary: #244593;
            --success-bg: #e8f6ef;
            --success-border: #c5e7d5;
            --success: #087344;
            --avatar-bg: #e9eefb;
            --avatar-text: #294a9b;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            background: var(--page-bg);
            color: var(--text);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI",
                Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        main {
            width: 100%;
            min-height: 100vh;
            padding: 31px 24px;
        }

        .verify-layout {
            display: grid;
            grid-template-columns: 275px minmax(0, 1fr);
            /* grid-template-columns: 272px minmax(0, 1fr); */
            column-gap: 20px;
            align-items: start;
            max-width: fit-content;
            /* max-width: 732px; */
            margin: 0 auto;
        }

        .page-title {
            margin: 0 0 11px;
            /* font-size: 21px; */
            line-height: 25px;
            font-weight: 750;
            letter-spacing: -0.55px;
            color: #172437;
        }

        .verification-card {
            width: 100%;
            min-height: 95px;
            padding: 17px 15px 14px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 9px;
            box-shadow: 0 1px 3px rgba(25, 32, 44, 0.035);
        }

        .verification-card label {
            display: block;
            margin: 0 0 7px;
            font-size: 14px;
            line-height: 11px;
            font-weight: 700;
            color: #172437;
        }

        .verify-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 50px;
            gap: 5px;
            align-items: end;
        }

        .code-input {
            width: 100%;
            height: 30px;
            padding: 0 10px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            outline: none;
            background: #fff;
            color: #1d2a3c;
            font: 400 12px/1 Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.015);
        }

        .code-input::placeholder {
            color: #a3a7ad;
            opacity: 1;
        }

        .code-input:focus {
            border-color: #9baedc;
            box-shadow: 0 0 0 2px rgba(36, 69, 147, 0.08);
        }

        .verify-button {
            height: 30px;
            border: 0;
            border-radius: 6px;
            background: var(--primary);
            color: #fff;
            font: 700 12px/1 Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            cursor: pointer;
        }

        .verify-button:hover {
            filter: brightness(0.96);
        }

        .help-text {
            margin: 6px 0 0;
            font-size: 10px;
            line-height: 10px;
            color: #657080;
        }

        .results {
            min-width: 0;
        }

        .results-heading {
            margin: 0 0 8px;
            font-size: 14px;
            line-height: 10px;
            font-weight: 750;
            letter-spacing: 0.75px;
            color: #647080;
            text-transform: uppercase;
        }

        .success-card {
            min-height: 80px;
            padding: 13px 19px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            border-radius: 10px;
        }

        .success-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--success);
            position: relative;
        }

        .success-icon::after {
            content: "";
            width: 15px;
            height: 8px;
            border-left: 3px solid #fff;
            border-bottom: 3px solid #fff;
            transform: rotate(-45deg) translate(1px, -1px);
            border-radius: 1px;
        }

        .success-content {
            min-width: 0;
        }

        .success-title {
            margin: 0 0 2px;
            /* font-size: 17px; */
            line-height: 20px;
            font-weight: 750;
            letter-spacing: -0.25px;
            color: #075e3a;
        }

        .success-description {
            margin: 0;
            font-size: 12px;
            line-height: 12px;
            color: #326650;
        }

        .signer-card {
            min-height: 55px;
            margin-top: 12px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 11px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 9px;
            box-shadow: 0 1px 3px rgba(25, 32, 44, 0.035);
        }

        .avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--avatar-bg);
            color: var(--avatar-text);
            font-size: 10px;
            line-height: 1;
            font-weight: 700;
        }

        .signer-label {
            margin: 0 0 1px;
            font-size: 10px;
            line-height: 9px;
            color: #7b8390;
        }

        .signer-name {
            margin: 6px 0px 4px 0px;
            font-size: 14px;
            line-height: 11px;
            font-weight: 700;
            color: #172437;
        }

        .signer-position {
            margin: 0;
            font-size: 12px;
            line-height: 9px;
            color: #647080;
        }

        .details-card {
            min-height: 50px;
            margin-top: 12px;
            padding: 10px 15px;
            display: grid;
            grid-template-columns: max-content max-content max-content max-content;
            gap: 16px;
            align-items: center;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 9px;
            box-shadow: 0 1px 3px rgba(25, 32, 44, 0.035);
        }

        .detail-item {
            min-width: 0;
        }

        .detail-label {
            display: block;
            margin-bottom: 3px;
            font-size: 10px;
            line-height: 8px;
            color: #7b8390;
        }

        .detail-value {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 12px;
            /* line-height: 10px; */
            font-weight: 700;
            color: #172437;
        }

        @media (max-width: 700px) {
            main {
                padding: 24px 18px;
            }

            .verify-layout {
                grid-template-columns: 1fr;
                gap: 24px;
                max-width: 440px;
            }

            .verification-card {
                min-height: auto;
            }

            .results {
                width: 100%;
            }
        }

        @media (max-width: 420px) {
            main {
                padding: 20px 14px;
            }

            .page-title {
                font-size: 20px;
            }

            .verify-form {
                grid-template-columns: 1fr;
            }

            .verify-button {
                width: 100%;
            }

            .success-card {
                align-items: flex-start;
                padding: 14px;
            }

            .success-icon {
                width: 36px;
                height: 36px;
                flex-basis: 36px;
            }

            .success-title {
                font-size: 15px;
            }

            .details-card {
                grid-template-columns: 1fr 1fr;
                gap: 12px 10px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="verify-layout">

            <aside aria-labelledby="verify-title">
                <h1 id="verify-title" class="page-title">Verify PO</h1>

                <section class="verification-card" aria-label="Purchase order verification">
                    <form class="verify-form">
                        <div>
                            <label for="unique-code">Unique code</label>
                            <input id="unique-code" class="code-input" type="text" placeholder="Enter unique code"
                                autocomplete="off">
                        </div>

                        <button class="verify-button" type="submit">Verify</button>
                    </form>

                    <p class="help-text">
                        No code yet? Ask the signer or the Procurement team.
                    </p>
                </section>
            </aside>

            <section class="results" aria-labelledby="results-title">
                <h2 id="results-title" class="results-heading">
                    Result &amp; Approval Details
                </h2>

                <article class="success-card" aria-label="Verification successful">
                    <span class="success-icon" aria-hidden="true"></span>

                    <div class="success-content">
                        <h3 class="success-title">Signature verified</h3>
                        <p class="success-description">
                            The code matches. Here are the approval details.
                        </p>
                    </div>
                </article>

                <article class="signer-card" aria-label="Signer information">
                    <div class="avatar" aria-hidden="true">WT</div>

                    <div>
                        <p class="signer-label">Signed by</p>
                        <p class="signer-name">Wisnu Trenggono Wirayuda</p>
                        <p class="signer-position">Staff</p>
                    </div>
                </article>

                <section class="details-card" aria-label="Purchase order details">
                    <div class="detail-item">
                        <span class="detail-label">PO number</span>
                        <span class="detail-value">PO/2026/09/0148</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Vendor</span>
                        <span class="detail-value">Supplier Nusa Indonesia</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Total amount</span>
                        <span class="detail-value">Rp 8.000.000.000</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Approval date</span>
                        <span class="detail-value">19 Sep 2026</span>
                    </div>
                </section>
            </section>

        </div>
    </main>
</body>

</html>